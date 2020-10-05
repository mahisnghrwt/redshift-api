# Redshift API

### Intro
---------------------

### Installation
---------------------

### Usage
---------------------

#### Endpoints
---------------------
| status                    | desc                    |           | method  | endpoint          | authentication |
| ------------------------- | ----------------------  | --------- | ------- | ----------------- | -------------- |
|   :white_check_mark:      | Submit calculations     |           | POST    | /api/             | REQUIRED       |
|   :white_check_mark:      | '' as Guest             | [single]  | POST    | /api/guest/       |                |
|   :white_check_mark:      | Fetch list of methods   |           | GET     | /api/methods/     |                |
|   :white_check_mark:      | Get Calculation status  | [single]  | GET     | /api/status/xxx   |                |
|   :white_check_mark:      | Get Calculation status  |           | POST    | /api/status/      | REQUIRED       |

Upcoming
[] Get Redshift result      POST            /api/redshift/xxx           REQUIRED
