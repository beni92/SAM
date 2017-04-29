{% if depot is defined and depot is not empty %}
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

    .clickable {
        cursor: pointer;
    }

    .clickable:hover {
        background-color: #f7f7f7;
    }
</style>
<h2>Depot from {{ depot.getUser().getLoginName() }}</h2>
<ul>
    <li>id: {{ depot.getId() }}</li>
    <li>budget: $ <?php echo(number_format($depot->getBudget(), 2)) ?></li>
    <li>value: $ <?php echo(number_format($depot->getValue(), 2)) ?></li>
</ul>
<h3>Stocks owned by the customer</h3>
<table style="">
    <thead >
    <tr >
        <th>Stock Symbol</th>
        <th style="text-align: left">Price Per Share<br>(from transaction)<br>(on average)</th>
        <th>Shares</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    {% if stocks is not empty %}
    {% for ownedStock in stocks %}
        <tr class="clickable" data-href="{{ url("dashboard/customer/" ~ depot.getUser().getLoginName() ~ "?depot=" ~ depot.getId() ~ "&stock=" ~ ownedStock.getStockSymbol()) }}">
            <td>{{ ownedStock.getStockSymbol() }}</td>
            <td>$ <?php echo(number_format($ownedStock->getPricePerShare(), 2)) ?></td>
            <td><?php echo(number_format($ownedStock->getShares(), 2)) ?></td>
        </tr>
    {% endfor %}
    {% endif %}
    </tbody>
</table>

<h3>Shares owned by the customer</h3>

<table style="">
    <thead >
    <tr >
        <th>Stock Symbol</th>
        <th>Price Per Share <br>(from transaction)</th>
        <th>Shares</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    {% for ownedStock in depot.getOwnedStocks() %}
        <tr>
            <td>{{ ownedStock.getStockSymbol() }}</td>
            <td>$ <?php echo(number_format($ownedStock->getPricePerShare(), 2)) ?></td>
            <td><?php echo(number_format($ownedStock->getShares(), 2)) ?></td>
            <td>
                {{ form() }}
                <input type="hidden" name="direction" value="sell">
                <input type="hidden" name="shares" value="{{ ownedStock.getShares() }}">
                <input type="hidden" name="symbol" value="{{ ownedStock.getStockSymbol() }}">
                <input type="hidden" name="depot" value="{{ depot.getId() }}">
                <input type="hidden" name="ownedStockId" value="{{ ownedStock.getId() }}">
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
<script type="text/javascript">
    $(document).ready(function() {
        $(".clickable").click(function() {
            window.location = $(this).data("href");
        });
    });
</script>
{% else %}
<h1>401</h1>
{% endif %}