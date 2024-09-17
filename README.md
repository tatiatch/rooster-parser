## Rooster Parser

This is a laravel application that can parser flight schedules. The example format for the flight schedule rooster can be found under <b>roosters</b> directory


## Available API endpoints

POST /api/upload-excel `(params: {file: string; deleteRecords: boolean})` // if deleteRecords is true db will be truncated and new records will replace the existing ones
<br/>
GET /api/events-between?start_date=2022-01-01&end_date=2022-01-31 <br />
GET /api/flights-by-location?location=KRP <br />
GET /api/flights-next-week <br />
GET /api/standby-next-week <br />
