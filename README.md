# aa-meeting-search-json-rpc
A library that creates a json meeting search function

## Instructions:
1. PHP 7 is required. Please install, if necessary.
2. git clone https://github.com/nightelf/aa-meeting-search-json-rpc.git
3. Create a .env file in the root dir with the following keys: MEETINGS_API_USER, MEETINGS_API_PASSWORD. See .env.example file.
4. Create an attendees csv file with each line having: \<Full name\>,\<email address\>,\<preferred meeting day\>,"\<address\>"

   Example:
```csv
  Dave Bautista,dave.bautista3344556677@zzmail.com,Monday,"123 Park Lane, San Diego, CA 92102"
  Harry Potter,harry.potter3344556677@zzmail.com,Tuesday,"444 Mercado Drive, San Diego, CA 92014"
```
5. from the command line, run this command with 3 arguments:
```sh
php find_and_sort_meetings.php <path-to-attendees-file.csv> <Meeting search city> <state code>
```
