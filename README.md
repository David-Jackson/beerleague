# beerleague
Why split the tab when there's an app for that?

## Frontend
The frontend of the beerleague uses HTML, Javascript, and CSS. It utilizes a framework called [Materialize](http://materializecss.com/) for the material design style. Javascript is used to parse the drinkers and trips data to calculate who's next to pay, the current standings, and the leaderboard.

### Process
1. Backend script ([get_drinkers.php](scripts/php/get_drinkers.php) and [get_trips.php](scripts/php/get_trips.php)) loads drinkers and trips from MySQL tables into drinkers and trips variables in Javascript.
2. The ```summarize()``` function is then called. This calculates metrics for each drinker that is stored in the ```summary``` variable.
```javascript
summary = {
  [drinker_id] : {
    id: [drinker_id],
    name: [drinker_name],
    paidCount: [number_of_times_paid],
    tripCount: [number_of_trips],
    delta: [current_standing], // negative numbers mean they have drank more than they have paid
                               // the most negative is who's next
    total: [total_value_of_all_drinks],
    retired: [boolean_if_retired] // true = retired, false = still active
  }, 
  ...
};
```
3. After ```summary``` is calculated, the following functions are called:
  - ```showWhosNext();``` inserts the first card on the webpage showing who is next to pay.
  - ```showStandings();``` inserts the second card with a table of the current standings.
  - ```showLeaderboard();``` inserts the third card with a table of the leaderboard.


## Backend
The backed has a MySQL database to store all drinkers and trips. PHP is used to access this data and inject it into the Javascript of the webpage. 

### beerleague.sql
beerleague is a MySQL database containing two tables: drinkers and trips.

#### drinkers table
| id | name | retired |
| --- | --- | --- |
| varchar | varchar | boolean |
| Unique ID for each drinker | Name of drinker | True if drinker is now retired (their name will no longer show up in who's next and current standings |

#### trips table
| id | date | bar | tab | paidBy | attendees |
| --- | --- | --- | --- | --- | --- |
| int | date | varchar | float | varchar | varchar |
| Unique ID for each trip | Trip date | Name of bar | Trip tab | Who paid the tab | Comma delimited array of all attendees (if someone brought guests, their name shows up multiple times) |
