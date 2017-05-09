# beerleague
Why split the tab when there's an app for that?

## Frontend
The frontend of the beerleague uses HTML, Javascript, and CSS. It utilizes a framework called [Materialize](http://materializecss.com/) for the material design style. Javascript is used to parse the drinkers and trips data to calculate who's next to pay, the current standings, and the leaderboard.

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
