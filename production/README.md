## Deploying to a remote machine

1. Launch a Linux server with any provider

2. Open ports `22, 80, 443, 2376, 2377, 7946, 7946, 4789`

3. Provision Docker from your local machine:

    ```
    docker-machine create --driver=generic --generic-ip-address=1.2.3.4 --generic-ssh-key=key.pem --generic-ssh-user=user vm
    ```

4. Check the available machines:

    ```
    docker-machine ls
    ```

5. Change you local environmental variables to reflect the remote machine:

    ```
    eval "$(docker-machine env vm)"
    ```

6. Check what containers are running on the remote machine (should be none):

    ```
    docker ps
    ```

7. Deploy

    ```
    docker-compose up -d
    ```