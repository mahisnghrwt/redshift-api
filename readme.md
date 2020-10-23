# Redshift API

### Intro

### Installation

### Usage

#### Endpoints

| status                    | description             |           | method  | endpoint          | authentication |
| ------------------------- | ----------------------  | --------- | ------- | ----------------- | -------------- |
|   :white_check_mark:      | Submit calculations     |           | `POST`  | ```/api/```             | *required*   |
|   :white_check_mark:      | '' as Guest             | [single]  | `POST`  | ```/api/guest/```       |                |
|   :white_check_mark:      | Fetch list of methods   |           | `GET`   | ```/api/methods/```     |                |
|   :white_check_mark:      | Get Calculation status  |           | `POST`  | ```/api/status/```      | *required*   |
|   :white_check_mark:   | Get Redshift result     |           | `POST`  | ```/api/result/```      | *required*   |
|   :white_check_mark:   | Get System load     |           | `POST`  | ```/api/system-load/```      | *required*   |

### Development
- [ ] Complete the API documentation