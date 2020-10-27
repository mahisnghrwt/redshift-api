# /status `POST` `Content-Type: application/json`
Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent bibendum lacinia aliquam. Integer lacinia blandit.

### Authentication Token required?
**Yes**

### Authorization
* General User - Can only access the status for calculations submitted by the current user.
* Admin - Can access status for any user.

### Sample JSON Request
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

### Sample JSON Response
```

```
### Attributes' Details
| Attribute | Can be Null? | Datatype |
|-----------|--------------|----------|
| `calculation_ids` | *no* | `array<int>` |
| `metadata:token` | *no* | `string` |