<h1 style="text-decoration:underline;">Registruoju.lt website</h1>

<h2>Setting Up Project after cloning</h2>
<p>Laravel projects requires extra setups before it can run on your local computers.</p>
<p>
    First we will install Node Module and Vendor files:<br>
    <br>
    <p>Run this in terminal:</p>
    <strong>npm i</strong><br>
    <strong>composer install</strong><br>
</p>

<h2 style="text-decoration:underline;">Setup .env file</h2>
<p>
To setup your .env, kindly duplicate your .env.example file and rename the duplicated file to .env.
</p>

<h2 style="text-decoration:underline;">Setup Database</h2>
<p>On your .env file, locate this block of code below.<br>
<br>
<b>
DB_CONNECTION=mysql<br>
DB_HOST=127.0.0.1<br>
DB_PORT=3306<br>
DB_DATABASE=subnetweb<br>
DB_USERNAME=<i>Your DB name</i><br>
DB_PASSWORD=<i>Your DB pass</i><br>
</b>
</p>

<h2 style="text-decoration:underline;">Final steps</h2>
<p>To finish setup, enter commands one by one in the terminal:</p>
<b>
php artisan key:generate<br>
php artisan migrate <br>
php artisan db:seed <br>
npm run dev<br>
</b>

<h2 style="text-decoration:underline;">To open project in the website you need to run:</h2>
<strong>php artisan serve</strong>

<h2>Login as admin:</h2>
<b>Email => admin@exapmle.com<br>
Password => adminpassword</br>
</b>
<h2>Uploaded Pictures</h2>
<p>To see uploaded pictures you need to run this:</p>
<b>php artisan storage:link</b><br>
<br>
<br>
<br>
<p><b>Project is in version 1.0</b></p>
