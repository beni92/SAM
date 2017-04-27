<h1>Dashboard</h1>
<h2>{{ user.getLoginName() }}</h2>
<ul>
    <li>First-name: {{ user.getFirstname() }}</li>
    <li>Surname: {{ user.getLastname() }}</li>
    {% if user.getBudget() is not null %}
    <li>Budget: {{ user.getBudget() }}</li>
    {% endif %}
</ul>
{% if user.getDepots() is not null %}
<h2>Depots</h2>
<ol>
    {% set counter = 1 %}

    {% for depot in user.getDepots() %}

    <li> <a href="">Depot {{ counter }}: Budget {{ depot.getBudget() }}</a></li>
        {% set counter = counter +1 %}
    {% endfor %}

</ol>
{% endif %}

{% if user.getTransactions() is not null %}
<h2>Transactions</h2>
<ul>
    {% for transaction in user.getTransactions() %}
    <li><a href="">Transaction {{ transaction.id }}</a></li>
    {% endfor %}
</ul>

{% endif %}

<h2>Actions</h2>
{% if user.getRole() === config.roles.customers %}
<ul>
    <li><a href="">Create Depot</a></li>
    <li><a href="">Add Money</a></li>
    <li><a href="">Remove Money</a></li>
    <li><a href="{{ url('index/logout') }}">Logout</a></li>
</ul>
{% endif %}
{% if user.getRole() === config.roles.employees %}
<ul>
    <li><a href="{{ url('dashboard/customer') }}">View Customers</a></li>
    <li><a href="{{ url('dashboard/addCustomer') }}">Add Customer</a></li>
    <li><a href="{{ url('dashboard/addEmployee') }}">Add Employee</a></li>
    <li><a href="">Get Volume</a></li>
    <li><a href="{{ url('index/logout') }}">Logout</a></li>
</ul>
{% endif %}