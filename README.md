# Welcome to Kanban board for Github issues

## A Little About It

A simple, read-only, Kanban-board for Github issues.

### Concepts and workflow

* `Queued:` are open issues, in a milestone with no one assigned
* `Active:` are any open issue, in a milestone with someone assigned
   * Active issues can, optionally, be paused by adding any of the configured "pause labels" to the issue
* `Completed:` are any issues in a milestone that is closed

#### Required environment variables

* `GH_CLIENT_ID`
* `GH_CLIENT_SECRET`
* `GH_ACCOUNT`
* `GH_REPOSITORIES`

----

