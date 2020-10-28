###### [Home](../) • [Endpoints](README.md) • [Authentication and Authorization](../authentication-authorization.md) • [Script](../script.md)
---

# /status `POST` `Content-Type: application/json`
Returns status of calculations, can be either 'SUBMITTED', 'PROCESSING', 'COMPLETED' or 'FAILED'.

### Authentication Token required?
**Yes**

### Authorization
* General User - Can only access the status for calculations submitted by themselves
* Admin - Can access status for any user.

### Sample JSON Request
```
{
    "calculation_ids": [17, 18],
    "metadata": {
        "token": "k4edH5LHuJqDCe2ZRlbCuu9BX9NdAN0p\/b51OnUG2kQfxi"
    }
}
```

### Sample JSON Response
```
{
    "status": {
        "17": {
            "2": {
                "status": "COMPLETED",
                "error_log": null
            },
            "7": {
                "status": "COMPLETED",
                "error_log": null
            }
        },
        "18": {
            "2": {
                "status": "COMPLETED",
                "error_log": null
            },
            "7": {
                "status": "COMPLETED",
                "error_log": null
            }
        }
    },
    "errors": []
}
```
### Attributes' Details

| Attribute | Can be Null? | Datatype |
|-----------|--------------|----------|
| `calculation_ids` | *no* | `array<int>` |
| `metadata:token` | *no* | `string` |
