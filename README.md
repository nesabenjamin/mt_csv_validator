# mt_csv_validator
Laravel CSV Validator


<h4 class="text-center mb-4">Steps to validate  </h4>

<ul class="list-group">
   
  <li class="list-group-item"> Run composer install</li>
  <li class="list-group-item"> create .env file and update db credentials</li>
  <li class="list-group-item">Run migration, seeder</li>
  <li class="list-group-item">
    Configure add/rename columns and set column validation in the route http://127.0.0.1:8000/configure
  </li>
  <li class="list-group-item">
    Update mailtrap credentials in env file
  </li>
  <li class="list-group-item">
    Run ‘php artisan queue:work’ command. 
  </li>

  <li class="list-group-item">
    Make a Post API request in postman to http://127.0.0.1:8000/api/store with file field: csv_file
  </li>

  <li class="list-group-item">
    Validation details will be listed in mail 
  </li>

</ul>
