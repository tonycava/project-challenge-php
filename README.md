<h1 id="top" align="center">LAphant</h1>
  <p align="center">
    Not scared of bugs
    <br />
  </p>

### Getting Started

<hr>

### Prerequisites

This is an overview of the stack use in this project

* Docker
  ```url
  https://www.docker.com/get-started/
  ```

* PHP
  ```url
    https://www.php.net/downloads.php/
  ```  

* Composer
  ```url
    https://getcomposer.org/download/
  ```

### Installation

<hr>

Installation of the project in your desktop for use it now

1. Clone the repo
   ```shell
   git clone https://ytrack.learn.ynov.com/git/ACAVAGNE/project_challenge_php.git
   ```

2. Place you in the root of the project
   ```shell
   cd project_challenge_php/
   ```

3. Create your discord by follow this link
   ```url
   https://www.youtube.com/watch?v=QLeGnjDLCBk&t=535s/
   ```

4. When it's done paste the discord token in the
   DISCORD_TOKEN environment variable in the laphant-bot container
   ```dotenv
   DISCORD_TOKEN: YOUR_TOKEN
   ```

5. Go on ``bot.php`` and this variable at the top of the page
   ```php
    const WEBHOOK_USERNAME = 'YOUR_WEBHOOK_USERNAME'; // also on api-php/send-message/discord change the username
    const BOT_USERNAME = 'YOUR_BOT_USERNAME';
    const SERVER_IDENTIFIER = 'YOUR_SERVER_IDENTIFIER';
    const CHANNEL_ID = 'YOUR_CHANNEL_ID';
   ```

6. Run the application
   ```shell
   make deploy
   ```

### Usage

<hr>

Go on localhost:8080 to see your WordPress site !

<p align="right">(<a href="#top">back to top</a>)</p>