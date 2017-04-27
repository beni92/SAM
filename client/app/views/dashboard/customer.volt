{% if customers is defined %}
    <h1>Customers</h1>
    {% if customers|length > 0 %}
        {{ form("dashboard/customer/search", "method":"GET") }}
        <label for="search">Search</label>
        {{ text_field("search", "placeholder":"Search ...") }}
        {{ submit_button("Find") }}
        {{ end_form() }}
    <ul>
        {% for customer in customers %}
            <li><a href="{{ url('dashboard/customer/'~customer.getLoginName()) }}">{{ customer.getFirstname() }} {{ customer.getLastname() }}</a></li>
        {% endfor %}
    </ul>
    {% else %}
    <h2>No customers found</h2>
    {% endif %}
{# Not a list but a single Customer is given #}
{% elseif customer is defined %}
    <h2>{{ customer.getLoginName() }}</h2>
    <ul>
        <li>First-name: {{ customer.getFirstname() }}</li>
        <li>Surname: {{ customer.getLastname() }}</li>
        <li>Budget: {{ customer.getBudget() }}</li>
    </ul>
    {% if customer.getDepots() is not null %}
        <h2>Depots</h2>
        <ul>
            {% set counter = 1 %}
            {% for depot in customer.getDepots() %}
                <li> <a href="{{ url("dashboard/customer/" ~ customer.getLoginName() ~ "?depot=" ~ depot.getId()) }}">Depot {{ counter }}: Budget {{ depot.getBudget() }}</a></li>
                {% set counter = counter +1 %}
            {% endfor %}
        </ul>
    {% endif %}
{% elseif depot is defined %}
    <h2>Depot from </h2>
    {{ depot.getId() }}
{% endif %}