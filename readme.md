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
|   :white_check_mark:      | Get Calculation status  | [single]  | `GET`   | ```/api/status/xx```    |                |
|   :white_check_mark:      | Get Calculation status  |           | `POST`  | ```/api/status/```      | *required*   |
|   :black_square_button:   | Get Redshift result     | [single]  | `GET`   | ```/api/result/xx```   |                |
|   :black_square_button:   | Get Redshift result     |           | `POST`  | ```/api/result/```      | *required*   |

### Development
- [x] Remove invalid characters after token is decrypted
- [ ] Complete the API documentation
- [ ] Fix JSON response format
- [ ] Update status of calculations accordingly
- [ ] Generate graphs
