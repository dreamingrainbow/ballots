**Create a Ballot Let the people Vote!**

Create A Ballot
[POST] `/Create-Ballot`
-->Send
```json
{
    "name"          :      String,
    "description"   :      String,
    "process"       :      "Create-Ballot"
}
```

Cast your Ballot
[POST] `/Cast/:ballot_id`
-->Send
```json
{
    "ballot_id"     :       Int,
    "abstain"       :       true|false  ||    default true,
    "yea"           :       true|false  ||    default false,
    "nea"           :       true|false  ||    default false,
    "process"       :       "Cast-Ballot"
}
```

View Ballot
[GET] `/Ballot/:ballot_id`

View Ballot Results
[GET] `/Results/:ballot_id`

