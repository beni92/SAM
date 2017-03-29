<h1>Dashboard</h1>
<h2>{{ user.getLoginName() }}</h2>
<ul>
    <li>First-name: {{ user.getFirstname() }}</li>
    <li>Surname: {{ user.getLastname() }}</li>
    <li>Budget: {{ user.getBudget() }}</li>
</ul>
<h2>Depots</h2>
<ol>
    {% set counter = 1 %}
    {% for depot in user.getDepots() %}

    <li> <a href="">Depot {{ counter }}: Budget {{ depot.getBudget() }}</a></li>
        {% set counter = counter +1 %}
    {% endfor %}
</ol>