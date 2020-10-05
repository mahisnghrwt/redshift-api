Task list
[] Fix the Database class
[] Find and integrate a basic php Router    -   DONE

Endpoints
                                                                        AUTHENTICATION
[]  Submit calculations     POST            /api/                       REQUIRED
[]  '' as Guest             POST            /api/guest/                 
[] Fetch list of methods    GET             /api/methods/               

Upcoming Endpoints
[] Get Calculation status   GET | POST      /api/status/xxx             REQUIRED
[] Get Redshift result      POST            /api/redshift/xxx           REQUIRED
                                            /api/calculation/xxx        REQUIRED



All POST requires JSON validation
JSON validation is implicit with AUTHENTICATION
