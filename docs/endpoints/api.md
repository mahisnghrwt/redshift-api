# / `POST` `Content-Type: application/json`
Performs multiple redshift estimation calculation within a single request.

### Authentication Token required ?
**Yes**

### Authorization
* General User
* Admin

### Sample JSON Request
Request must be an array, where the last element stores metadata, including `job_id`, `token` and `methods` specifying what methods to use for redshift estimation. All other element must contain the measurements required for the calculations, refer to the Attributes' Detail for more info.
```
{
    "data": {
        "assigned_calc_id": "My Galaxy",
        "optical_u": 87,
        "optical_v": 9786,
        "optical_g": 79786,
        "optical_r": 786,
        "optical_i": 6,
        "optical_z": 98,
        "infrared_three_six": 897,
        "infrared_four_five": 6,
        "infrared_five_eight": 54,
        "infrared_eight_zero": 876,
        "infrared_J": 978,
        "infrared_H": 876,
        "infrared_K": 76,
        "radio_one_four": 9768
    },
    "methods": [
        2, 4
    ]
}
```

### Sample JSON Response
As a response, one of the attribute is `calculation_ids` array. Its each element corresponds to the calculation in the request. These can be seen as *receipt* of a calculation, and is used to retrieve status and result of a calculation.
```
{
    "errors": [],
    "result": {
        "2": {
            "redshift_result": "104080",
            "redshift_alt_result": null
        },
        "4": {
            "redshift_result": null,
            "redshift_alt_result": "http://redshift-01.cdms.westernsydney.edu.au/redshift/api/graph/5f96265c6c1be"
        }
    }
}
```

### Attributes' Details
`:` represents the nested attributes, for example `data:assigned_calc_id`, represents
```
"data": {
    "assigned_calc_id": "My Galaxy"   
}
```

#### Measurements
These are the measurements methods will use to estimate redshift.

| Attribute | Can be Null? | Datatype |
|-----------|--------------|----------|
| `data:assigned_calc_id` | *yes* | `string` |
| `data:optical_u` |  | `numeric` |
| `data:optical_v` |  | `numeric` |
| `data:optical_g` |  | `numeric` |
| `data:optical_r` |  | `numeric` |
| `data:optical_i` |  | `numeric` |
| `data:optical_z` |  | `numeric` |
| `data:infrared_three_six` |  | `numeric` |
| `data:infrared_four_five` |  | `numeric` |
| `data:infrared_five_eight` | | `numeric` |
| `data:infrared_eight_zero` | | `numeric` |
| `data:infrared_J` |  | `numeric` |
| `data:infrared_H` |  | `numeric` |
| `data:infrared_K` |  | `numeric` |
| `data:radio_one_four` |  | `numeric` |

#### Methods
`methods` array contains `method_id` of those methods that will be used to perform the calculations.

| Attribute | Can be Null? | Datatype |
|-----------|--------------|----------|
| `methods` | *no* | `array<int>` |

### Sample cURL Command

#### Windows
```

```

#### Mac and Linux
```
```