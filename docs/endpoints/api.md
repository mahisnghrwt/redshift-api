###### [Home](../) • [Endpoints](README.md) • [Authentication and Authorization](../authentication-authorization.md) • [Script](../script.md)
---

# / `POST` `Content-Type: application/json`
Performs multiple redshift estimation calculation within a single request.

### Authentication Token required ?
**Yes**

### Authorization
* General User
* Admin

### Sample JSON Request
Request must be an array, where the last element stores metadata, refer to section 'metadata'. All other element must contain the measurements required for the calculations, refer to the 'measurements' for more info.
```
[
    {
        "assigned_calc_id": "Milky Way",
        "optical_u": 1,
        "optical_v": 2,
        "optical_g": 3,
        "optical_r": 4,
        "optical_i": 5,
        "optical_z": 6,
        "infrared_three_six": 7,
        "infrared_four_five": 8,
        "infrared_five_eight": 9,
        "infrared_eight_zero": 10,
        "infrared_J": 11,
        "infrared_H": 12,
        "infrared_K": 13,
        "radio_one_four": 14
    },
    {
        "assigned_calc_id": "Milky Way 2",
        "optical_u": 1,
        "optical_v": 2,
        "optical_g": 3,
        "optical_r": 4,
        "optical_i": 5,
        "optical_z": 6,
        "infrared_three_six": 7,
        "infrared_four_five": 8,
        "infrared_five_eight": 9,
        "infrared_eight_zero": 10,
        "infrared_J": 11,
        "infrared_H": 12,
        "infrared_K": 13,
        "radio_one_four": 14
    },
    {
        "job_id": 100,
        "methods": [
            2
        ],
        "token": "f+oaO91G\/C0asdasdasducZl99wY1FYBGgRimACty28Rbnb9Op66NM1VLK52epb\/YNzdX+0RvVyQ+wIOE1Irx4thTYGfOBoen3xMqom2Zly4cOcYZUmqBs0xCJaN+EpdLShm0eJ3VSaFveSFqLEaS+jJo9TnS9g=="
    }
]
```

### Sample JSON Response
As a response, one of the attribute is `calculation_ids` array. Its each element corresponds to the calculation in the request. These can be seen as *receipt* of a calculation, and is used to retrieve their status and result.
```
{
    "calculation_ids": [100, 101],
    "errors": []
}
```

### Attributes' Details
#### Measurements
These are the measurements methods will use to estimate redshift.

| Attribute | Can be Null? | Datatype |
|-----------|--------------|----------|
| `optical_u` |  | `numeric` |
| `optical_v` |  | `numeric` |
| `optical_g` |  | `numeric` |
| `optical_r` |  | `numeric` |
| `optical_i` |  | `numeric` |
| `optical_z` |  | `numeric` |
| `infrared_three_six` |  | `numeric` |
| `infrared_four_five` |  | `numeric` |
| `infrared_five_eight` | | `numeric` |
| `infrared_eight_zero` | | `numeric` |
| `infrared_J` |  | `numeric` |
| `infrared_H` |  | `numeric` |
| `infrared_K` |  | `numeric` |
| `radio_one_four` |  | `numeric` |

#### Metadata
`methods` array contains `method_id` of those methods that will be used to perform calculation. `token` is the Authentication Token.

| Attribute | Can be Null? | Datatype |
|-----------|--------------|----------|
| `methods` | *no* | `array<int>` |
| `job_id` | *no* | `string` |
| `token` | *no* | `string` |