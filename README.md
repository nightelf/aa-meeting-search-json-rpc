# aa-meeting-search-json-rpc
A library / command line utility that searches AA meetings for a city, filters them by day, sorts them by distance to a person's address, and emails the meeting info to the person.

## Instructions:
1. PHP 7 is required. Please install, if necessary.
2. Clone the git repository
```sh
git clone https://github.com/nightelf/aa-meeting-search-json-rpc.git
```

3. install the composer packages from the repository root directory
```sh
composer install
```

4. Create a .env file in the root dir with the following keys: MEETINGS_API_USER, MEETINGS_API_PASSWORD. For reference, see the .env.example file.

Example:
```TEXT
  MEETINGS_API_USER=sdkfjkeimfls
  MEETINGS_API_PASSWORD=sdkfjoi20vjlskdksjd
```

5. Create an attendees csv file with each line having: \<Full name\>,\<email address\>,\<preferred meeting day\>,"\<address\>". For reference, see the attendees.csv.example file.

Example:
```csv
  Dave Bautista,dave.bautista3344556677@zzmail.com,Monday,"123 Park Lane, San Diego, CA 92102"
  Harry Potter,harry.potter3344556677@zzmail.com,Tuesday,"444 Mercado Drive, San Diego, CA 92014"
```

6. from the command line, run this command with 3 arguments:
```sh
php find_and_sort_meetings.php <path-to-attendees-file.csv> <Meeting search city> <state code>
```

Example:
```sh
php find_and_sort_meetings.php ../attendees.csv "San Diego" CA
```

7. Wait for the AA meetings email to hit your inbox. It will have the subject "Some Alcoholics Anonymous meetings in the \<search city\> area". Some email apps, like Gmail, will filter the email to spam. So, you may need to look in your spam folder. Yahoo! Mail worked fine and did not spam filter the email. Dealing with spam filtering is beyond the scope of this exercise. :D
