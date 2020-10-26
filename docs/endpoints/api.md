# /api `POST` `Content-Type: application/json`

Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent bibendum lacinia aliquam. Integer lacinia blandit.

### Authorization
* User
* Admin

### Request

```
[
    {
        "assigned_calc_ID": string,
        "optical_u": number,
        "optical_v": number,
        "optical_g": number,
        "optical_r": number,
        "optical_i": number,
        "optical_z": number,
        "infrared_three_six": number,
        "infrared_four_five": number,
        "infrared_five_eight": number,
        "infrared_eight_zero": number,
        "infrared_J": number,
        "infrared_H": number,
        "infrared_K": number,
        "radio_one_four": number
    },
    ....
    {
        "job_id": int,
        "methods": [
            int,....
        ],
        "token": string
    }
]
```

### Response

```
{
    "calculation_ids": [int,....],
    "errors": [
        {
            "id": "string",
            "desc": "string"
        },
        ....
    ]
}
```

### Sample cURL Command(Windows)

```

```
