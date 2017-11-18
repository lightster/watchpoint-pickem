<?php

class BnetAuth
{
    public function __construct(array $options = [])
    {
        $this->key = $options['key'];
        $this->secret = $options['secret'];
        $this->redirect_uri = $options['redirect_uri'];
        $this->code = $options['code'];
        $this->state = $options['state'];
        $this->auth_handler = $options['auth_handler'];
    }

    public function getUser()
    {
        $access_token = $this->getAccessToken();
        $request = $this->getProvider()->getAuthenticatedRequest(
            'GET',
            'https://us.api.battle.net/account/user',
            $access_token
        );

        try {
            $client = new GuzzleHttp\Client();
            $response = $client->send($request);

            return json_decode($response->getBody(), true);
        } catch (GuzzleHttp\Exception\RequestException $e) {
            throw new BnetAuthException($e->getMessage());
        }
    }

    private function clearState()
    {
        unset($_SESSION['bnet_oauth2_state']);
    }

    private function getProvider()
    {
        if ($this->provider) {
            return $this->provider;
        }

        $this->provider = new \League\OAuth2\Client\Provider\GenericProvider([
            'clientId'                => $this->key,
            'clientSecret'            => $this->secret,
            'redirectUri'             => $this->redirect_uri,
            'urlAuthorize'            => 'https://us.battle.net/oauth/authorize',
            'urlAccessToken'          => 'https://us.battle.net/oauth/token',
            'urlResourceOwnerDetails' => '',
            'scopes'                  => '',
        ]);

        return $this->provider;
    }

    private function getAccessToken()
    {
        $provider = $this->getProvider();

        if (empty($this->code)) {
            $auth_url = $provider->getAuthorizationUrl();
            $_SESSION['bnet_oauth2_state'] = $provider->getState();

            return $this->auth_handler->call($this, $auth_url);
        }

        if (empty($this->state) || empty($_SESSION['bnet_oauth2_state'])
            || $this->state !== $_SESSION['bnet_oauth2_state']) {
            $this->clearState();
            throw new BnetAuthException('Invalid state');
        }

        try {
            $access_token = $provider->getAccessToken('authorization_code', [
                'code' => $this->code
            ]);
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            throw new BnetAuthException($e->getMessage());
        } finally {
            $this->clearState();
        }

        return $access_token;
    }
}
