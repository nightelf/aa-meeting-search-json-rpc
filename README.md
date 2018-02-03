# aa-meeting-search-json-rpc
A library / command line utility that searches AA meetings and emails them to people.

## Instructions:
1. PHP 7 is required. Please install, if necessary.
2. git clone https://github.com/nightelf/aa-meeting-search-json-rpc.git
3. Create a .env file in the root dir with the following keys: MEETINGS_API_USER, MEETINGS_API_PASSWORD. For reference, see the .env.example file.

   Example:
```csv
  Dave Bautista,dave.bautista3344556677@zzmail.com,Monday,"123 Park Lane, San Diego, CA 92102"
  Harry Potter,harry.potter3344556677@zzmail.com,Tuesday,"444 Mercado Drive, San Diego, CA 92014"
```
4. Create an attendees csv file with each line having: \<Full name\>,\<email address\>,\<preferred meeting day\>,"\<address\>". For reference, see the attendees.csv.example file.

   Example:
```TEXT
  MEETINGS_API_USER=sdkfjkeimfls
  MEETINGS_API_PASSWORD=sdkfjoi20vjlskdksjd
```
5. from the command line, run this command with 3 arguments:
```sh
php find_and_sort_meetings.php <path-to-attendees-file.csv> <Meeting search city> <state code>
```
6. Wait for the AA meetings email to hit your inbox. Some email apps, like Gmail, will filter the email to spam. So, you may need to look in your spam folder. Yahoo! Mail worked fine and did not spam filter the email. Dealing with spam filtering is beyond the scope of this exercise. :D
