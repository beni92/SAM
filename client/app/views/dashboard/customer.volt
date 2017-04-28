{% if customers is defined %}
    <h1>Customers</h1>
    {% if customers|length > 0 %}
        {{ form("dashboard/customer/search", "method":"GET") }}
        <label for="search">Search</label>
        {{ text_field("search", "placeholder":"Search ...") }}
        <input type="hidden" class="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
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
{% elseif customer is defined and newDepot is not defined %}
    <h2>{{ customer.getLoginName() }}</h2>
    <ul>
        <li>First-name: {{ customer.getFirstname() }}</li>
        <li>Surname: {{ customer.getLastname() }}</li>
        <li>Budget: {{ customer.getBudget() + 0 }}$</li>
        {% set cash = 0 %}
        {% if customer.getDepots() is not null %}

            {% set value = 0 %}
            {% for depot in customer.getDepots() %}
                {% set cash += depot.getBudget() %}
            {% endfor %}
            <li>Cash in all Depots: {{ cash }}$</li>

        {% endif %}
        <li>Cash overall: {{ customer.getBudget() + cash }}$</li>
    </ul>
    {% if customer.getDepots() is not null %}
        <h2>Depots</h2>
        <ul>
            {% set counter = 1 %}
            {% for depot in customer.getDepots() %}
                <li> <a href="{{ url("dashboard/customer/" ~ customer.getLoginName() ~ "?depot=" ~ depot.getId()) }}">Depot {{ counter }}: Budget {{ depot.getBudget() + 0 }}$</a></li>
                {% set counter = counter +1 %}
            {% endfor %}
        </ul>
    {% endif %}

    <h2>Actions</h2>
    <ul>
        <li><a href="{{ url("dashboard/customer/" ~ customer.getLoginName() ~ "?depot&new") }}">Create new Depot</a></li>
        <li><a href="{{ url("dashboard/customer/" ~ customer.getLoginName() ~ "?budget=change") }}" >Change Budget</a></li>
    </ul>
{% elseif newDepot is defined and newDepot is true %}
    <h2>Create new Depot for {{ customer.getLoginName() }}</h2>
    <ul>
        <li>Max Budget: {{ customer.getBudget() }}$</li>
    </ul>
    <div style="float: left; width: 200px">
        {{ form("dashboard/customer/" ~ customer.getLoginName(), "method":"POST") }}
            <label for="Budget">Budget in $</label>
            {{ text_field("budget", "placeholder":"Set your Budget ..") }}
            <input type="hidden" name="newDepot" value="new">
            <input type="hidden" class="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
            {{ submit_button("Add") }}
        {{ end_form() }}
    </div>
{% elseif depot is defined and searchStocks is not defined %}
    <h2>Depot from </h2>
    <ul>
        <li>id: {{ depot.getId() }}</li>
        <li>budget: {{ depot.getBudget() + 0 }}$</li>
    </ul>
    <h3>Shares owned by the customer</h3>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {

            padding: 0.2em;
        }
        thead {
            background-color: #f7f7f7;
        }
        tr:nth-child(even) {
            background-color: rgba(0,0,0,0.2);
        }
    </style>
    <table style="">
        <thead >
            <tr >
                <th>Stock Symbol</th>
                <th>Price Per Share</th>
                <th>Shares</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            {% for ownedStock in depot.getOwnedStocks() %}
            <tr>
                <td>{{ ownedStock.getStockSymbol() }}</td>
                <td>{{ ownedStock.getPricePerShare() }}$</td>
                <td>{{ ownedStock.getShares() }}</td>
                <td>
                    {{ form() }}
                        <input type="hidden" name="direction" value="sell">
                        <input type="hidden" name="shares" value="{{ ownedStock.getShares() }}">
                        <input type="hidden" name="symbol" value="{{ ownedStock.getStockSymbol() }}">
                        <input type="hidden" name="depot" value="{{ depot.getId() }}">
                        <input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
                        <input type="submit" value="Sell">
                    {{ end_form() }}
                </td>
            </tr>
            {% endfor %}
        </tbody>
    </table>
    <h3>Actions</h3>
    <ul>
        <li><a href="{{ url('dashboard/customer/' ~ depot.getUser().getLoginName() ~ '?depot=' ~ depot.getId() ~ "&stock")  }}">Search/Buy stocks</a></li>

    </ul>
{% elseif depot is defined and searchStocks is defined %}
    <h2>Find Stocks</h2>
    {{ form('dashboard/customer/' ~ depot.getUser().getLoginName(), "method":"GET") }}
    <label for="stock">Search</label>
    {{ text_field("stock", "placeholder":"Search ...") }}
    <input type="hidden" class="hidden" name="depot" value="{{ depot.getId() }}">
    <input type="hidden" class="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">
    {{ submit_button("Find") }}
    {{ end_form() }}

    {% if stocks is defined %}
        <style>
            table {
                border-collapse: collapse;
            }
            th, td {

                padding: 0.2em;
            }
            thead {
                background-color: #f7f7f7;
            }
            tr:nth-child(even) {
                background-color: rgba(0,0,0,0.2);
            }
        </style>

        <table style="">
            <thead >
            <tr >
                <th>Company Name</th>
                <th>Stock Symbol</th>
                <th>Stock Exchange</th>
                <th>Last Trade Price</th>
                <th>Last Trade Time</th>
                <th>Float Shares</th>
                <th>Market Capitalization</th>
                <th>Owns</th>
                <th>Amount</th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            {% for stock in stocks %}
                {%  set owns = depot.getOwnedStocksShares(stock.getSymbol()) %}
                <tr>
                    <td>{{ stock.getCompanyName() }}</td>
                    <td>{{ stock.getSymbol() }}</td>
                    <td>{{ stock.getStockExchange() }}</td>
                    <td>{{ stock.getLastTradePrice() }}$</td>
                    <td>{{ stock.getLastTradeTime() }}</td>
                    <td>{{ stock.getFloatShares() }}</td>
                    <td>{{ stock.getMarketCapitalization() }}$</td>
                    <td>{{ owns }}</td>
                    <td><input data-symbol="{{ stock.getSymbol() }}" name="amount" placeholder="b or s" type="number" size="4" style="width: 4em" min="0"></td>
                    <td><input name="buy" type="button" value="Buy" data-symbol="{{ stock.getSymbol() }}"></td>
                    <td><input name="sell" type="button" value="Sell" data-symbol="{{ stock.getSymbol() }}"{% if owns is 0 %} disabled {% endif %}></td>
                </tr>
            {% endfor %}
            </tbody>
        </table>
        <script type="text/javascript">
            $(document).ready(function() {
                /**
                 * when clicked on any buy button get the surrounding inputs
                 */
                $("input[name='buy']").click(function() {
                    var symbol = $(this).data()["symbol"];
                    var shares = $('input[name="amount"][data-symbol="'+symbol+'"').val();
                    var form = createForm("buy", shares, symbol);

                    $(document.body).append(form);
                    $("#buyForm").submit();
                });

                /**
                 * when clicked on any buy button get the surrounding inputs
                 */
                $("input[name='sell']").click(function() {
                    var symbol = $(this).data()["symbol"];
                    var shares = $('input[name="amount"][data-symbol="'+symbol+'"').val();
                    var form = createForm("sell", shares, symbol);

                    $(document.body).append(form);
                    $("#sellForm").submit();
                });
            });
            function createForm(direction, shares, symbol) {
                symbol = symbol.trim();
                return '<form id="'+ direction + 'Form" action="{{ url('dashboard/customer/' ~ depot.getUser().getLoginName()) }}" method="POST">' +
                    '<input type="hidden" name="direction" value="'+direction+'">' +
                    '<input type="hidden" name="shares" value="'+shares+'">' +
                    '<input type="hidden" name="symbol" value="'+symbol+'">' +
                    '<input type="hidden" name="depot" value="{{ depot.getId() }}">' +
                    '<input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">';
            }
        </script>
    {% endif %}
{% elseif transaction is defined %}
    {% if transaction is not false %}
        <h3>Transaction Successful</h3>
    {% else %}
        <h3>Transaction Failed</h3>
    {% endif %}
    <a href="javascript:history.back()">Back</a>
{% endif %}