###### [Home](../) • [Endpoints](README.md) • [Authentication and Authorization](../authentication-authorization.md) • [Script](../script.md)
---

# /methods `GET` `Content-Type: application/json`
Return list of available methods along with their description and id.

### Authentication Token required ?
**No**

### Authorization
**Not applicable**

### Sample JSON Response
It returns an array available methods, containing `id`, `name` and `desc` for every single method, `id` is useful when performing calculations.
```
[
    {
        "id": "1",
        "name": "mean",
        "desc": "Average of all measurements"
    },
    {
        "id": "2",
        "name": "sum",
        "desc": "Sum of all measurements"
    },
    {
        "id": "3",
        "name": "minus",
        "desc": "subtracting all measurements"
    }
]
```