  
<h3>Chuushin</h3>
<ol>
  <li>Composer Install: <strong>composer install</strong></li>
  <li>NPM Install: <strong>npm install</strong></li>
  <li>
    Database: <strong>php artisan migrate --seed</strong>
    
  </li>
  <li>
  Remplir le .ENV
  </li>
  <li>
    Install passport clients: <strong>php artisan passport:install</strong>
    <ul>
        <li>
        Copy the Client Secret from <strong>Client ID: 2</strong> to your EVN variable VUE_CLIENT_ID<br>
        <img src="http://andranikbadalyan.com/laravelpassportvueauth/img/screenshot3.jpg">
        </li>
    </ul>
  </li>
  <li>
  Discord Setup
  </li>
  <li>
  npm run production
  </li>
  <li>run <strong>php artisan config:clear</strong></li>
  <li>
  Use Auth middleware for protected routes <strong>auth:api</strong>
    <ul>
        <li>test with GET: <strong>/api/user</strong> (already exists in routes/api.php)</li>
    </ul>
  </li>
</ol>
