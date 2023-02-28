# Woocommerce API panel

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
![Repo Size](https://img.shields.io/github/repo-size/sina-ghiasi/wc-api-panel)
![PHP version](https://img.shields.io/badge/PHP->=7.4-blue.svg)
![Bootstrap version](https://img.shields.io/badge/Bootstrap-5-blueviolet.svg)
![GitHub Repo stars](https://img.shields.io/github/stars/sina-ghiasi/wc-api-panel?style=social)

A panel to help :

- list all products
- filter products by category
- search in products based on their name
- bulk edit product prices in two ways :
  - edit in list
  - using a formula based on category

Previews :

![preview_pic_1](https://github.com/Sina-Ghiasi/wc-api-panel/blob/main/assets/img/panel-preview-RTL-1.png)
![preview_pic_2](https://github.com/Sina-Ghiasi/wc-api-panel/blob/main/assets/img/panel-preview-RTL-2.png)

## Deployment

To deploy this project you should do these step :

1. make a subdomain like panel.your-site-url and then put this repo files in that subdomain folder
2. make a .env file in subdomain folder with environment variables that listed in next section
3. use your subdomain to login into panel

## Environment Variables

To run this project, you will need to add the following environment variables to your .env file

Woocommerce rest API :
`WCP_STORE_URL`
`WCP_CONSUMER_KEY`
`WCP_CONSUMER_SECRET`
`WCP_WP_API`
`WCP_WC_VERSION`
`WCP_VERIFY_SSL`
`WCP_TIMEOUT`

Panel data :
`WCP_USERNAME`
`WCP_PASSWORD`

Database :
`WCP_SERVERNAME`
`WCP_DB_USERNAME`
`WCP_DB_PASSWORD`
`WCP_DB_NAME`
`WCP_DB_CHARACTER`
`WCP_DB_COLLATE`

For example :

```
WCP_STORE_URL="https://example.com"

WCP_CONSUMER_KEY="**************************************"

WCP_CONSUMER_SECRET="************************************"

WCP_WP_API=1

WCP_WC_VERSION="wc/v3"

WCP_VERIFY_SSL=0

WCP_TIMEOUT=180


WCP_USERNAME="admin"

WCP_PASSWORD="pass"


WCP_SERVERNAME = "localhost"

WCP_DB_USERNAME = "root"

WCP_DB_PASSWORD = "mysql"

WCP_DB_NAME = "mydb"

WCP_DB_CHARACTER = "utf8mb4"

WCP_DB_COLLATE = "utf8mb4_0900_ai_ci"

```
