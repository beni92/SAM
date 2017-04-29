{% if customer is defined and customer is not empty %}
<h2>{{ customer.getLoginName() }}</h2>
<ul>
    <li>First-name: {{ customer.getFirstname() }}</li>
    <li>Surname: {{ customer.getLastname() }}</li>
    <li>Address: {{ customer.getAddress() }}</li>
    <li>Budget: $ <?php echo(number_format($customer->getBudget(), 2)) ?></li>
    {% set cash = 0 %}
    {% set depVal = 0 %}
    {% if customer.getDepots() is not null %}

        {% set value = 0 %}
        {% for depot in customer.getDepots() %}
            {% set cash += depot.getBudget() %}
            {% set depVal += depot.getValue() %}
        {% endfor %}
        <li>Cash in all Depots: $ <?php echo(number_format($cash, 2)) ?></li>
        <li>Value over all Depots: $ <?php echo(number_format($depVal, 2)) ?></li>

    {% endif %}
    <li>Cash overall: $ <?php echo(number_format($customer->getBudget() + $cash, 2)) ?></li>

</ul>
{% if customer.getDepots() is not null %}
    <h2>Depots</h2>
    <ul>
        {% set counter = 1 %}
        {% for depot in customer.getDepots() %}
            <li> <a href="{{ url("dashboard/customer/" ~ customer.getLoginName() ~ "?depot=" ~ depot.getId()) }}">Depot {{ counter }}: Budget $ <?php echo(number_format($depot->getBudget(), 2)) ?></a></li>
            {% set counter = counter +1 %}
        {% endfor %}
    </ul>
{% endif %}

<h2>Actions</h2>
<ul>
    <li><a href="{{ url("dashboard/customer/" ~ customer.getLoginName() ~ "?depot&new") }}">Create new Depot</a></li>
    <li><a href="{{ url("dashboard/customer/" ~ customer.getLoginName() ~ "?budget=change") }}" >Change Budget</a></li>
    {% if this.session.get("auth").getLoginName() is customer.getLoginName() %}
        <li><a href="{{ url("index/logout") }}" >Logout</a></li>
    {% endif %}
</ul>
{% else %}
        <h1>404</h1>
{% endif %}