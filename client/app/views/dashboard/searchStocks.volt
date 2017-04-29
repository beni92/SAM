<h2>Find Stocks</h2>

{{ form('dashboard/customer/' ~ depot.getUser().getLoginName(), "method":"GET") }}
    <label for="stock">Search</label>
    <input type="hidden" class="hidden" name="depot" value="{{ depot.getId() }}">
    {{ text_field("stock", "placeholder":"Search ...") }}
    {{ submit_button("Find") }}
{{ end_form() }}

{% if stocks is defined and stocks is not empty %}
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
                <td>$ <?php echo(number_format($stock->getLastTradePrice(), 2)) ?></td>
                <td>{{ stock.getLastTradeTime() }}</td>
                <td><?php echo(number_format($stock->getFloatShares(), 2)) ?></td>
                <td>$ <?php echo(number_format($stock->getMarketCapitalization(), 2)) ?></td>
                <td><?php echo(number_format($owns, 2)) ?></td>
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
                '<input type="hidden" name="depot" value="{{ depot.getId() }}">';
        }
    </script>
{% else %}
    <h3>No stocks found</h3>
{% endif %}