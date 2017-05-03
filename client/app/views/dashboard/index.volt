{% if user is defined and user is not empty %}
<h1>Dashboard</h1>
<h2>{{ user.getLoginName() }}</h2>
<ul>
    <li>Forename: {{ user.getFirstname() }}</li>
    <li>Surname: {{ user.getLastname() }}</li>
</ul>

<h2>Actions</h2>
{% if user.getRole() === config.roles.employees %}
<ul>
    <li><a href="{{ url('dashboard/customer') }}">View Customers</a></li>
    <li><a href="{{ url('dashboard/addCustomer') }}">Add Customer</a></li>
    <li><a href="{{ url('dashboard/bank/') }}">Show Bank Info</a></li>
    <li><a href="{{ url('index/logout') }}">Logout</a></li>
</ul>
{% endif %}
{% else %}
<h1>401</h1>
{% endif %}
