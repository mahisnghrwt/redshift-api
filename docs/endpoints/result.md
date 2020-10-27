###### [Home](../) • [Endpoints](README.md) • [Authentication and Authorization](../authentication-authorization.md) • [Script](../script.md)
---

# /result `POST` `Content-Type: application/json`
Returns results of calculations.

### Authentication Token required?
**Yes**

### Authorization
* General User - Can only access the result for calculations submitted by the current user.
* Admin - Can access result for any user.

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
    "errors": [],
    "result": {
        "17": [
            {
                "redshift_result": "105.00000000",
                "redshift_alt_result": null,
                "calculation_id": "17",
                "method_id": "2"
            },
            {
                "redshift_result": "105.00000000",
                "redshift_alt_result": null,
                "calculation_id": "17",
                "method_id": "7"
            }
        ],
        "18": [
            {
                "redshift_result": "105.00000000",
                "redshift_alt_result": null,
                "calculation_id": "18",
                "method_id": "2"
            },
            {
                "redshift_result": "105.00000000",
                "redshift_alt_result": null,
                "calculation_id": "18",
                "method_id": "7"
            }
        ]
    }
}
```
### Attributes' Details

| Attribute | Can be Null? | Datatype |
|-----------|--------------|----------|
| `calculation_ids` | *no* | `array<int>` |
| `metadata:token` | *no* | `string` |