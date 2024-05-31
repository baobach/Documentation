<p>Follow these steps to implement the bear call spread strategy:</p>

<ol>
    <li>In the <code class="csharp">Initialize</code><code class="python">initialize</code> method, set the start date, end date, cash, and <a href="/docs/v2/writing-algorithms/universes/equity-options">Option universe</a>.</li>
    <div class="section-example-container">
        <pre class="csharp">private Symbol _symbol;

public override void Initialize()
{
    SetStartDate(2017, 2, 1);
    SetEndDate(2017, 3, 5);
    SetCash(500000);
    UniverseSettings.Asynchronous = true;
    var option = AddOption("GOOG", Resolution.Minute);
    _symbol = option.Symbol;
    option.SetFilter(universe =&gt; universe.IncludeWeeklys().Strikes(-15, 15).Expiration(0, 31));
}</pre>
        <pre class="python">def initialize(self) -&gt; None:
    self.set_start_date(2017, 2, 1)
    self.set_end_date(2017, 3, 5)
    self.set_cash(500000)
    self.universe_settings.asynchronous = True
    option = self.add_option("GOOG", Resolution.MINUTE)
    self._symbol = option.symbol
    option.set_filter(lambda universe: universe.include_weeklys().strikes(-15, 15).expiration(0, 31))</pre>
    </div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, select the contracts of the strategy legs.</li>
    <div class="section-example-container">
        <pre class="csharp">public override void OnData(Slice slice)
{
    if (Portfolio.Invested) return;

    // Get the OptionChain
    var chain = slice.OptionChains.get(_symbol, null);
    if (chain.Count() == 0) return;

    // Select the call Option contracts with the furthest expiry
    var expiry = chain.OrderByDescending(x =&gt; x.Expiry).First().Expiry;    
    var calls = chain.Where(x =&gt; x.Expiry == expiry &amp;&amp; x.Right == OptionRight.Call);
    if (calls.Count() == 0) return;

    // Select the ITM and OTM contract strike prices from the remaining contracts
    var orderedCalls = calls.OrderBy(x =&gt; x.Strike);
    var itmCall = orderedCalls.First();
    var otmCall = orderedCalls.Last();</pre>
        <pre class="python">def on_data(self, slice: Slice) -&gt; None:
    if self.portfolio.invested: return

    # Get the OptionChain
    chain = slice.option_chains.get(self._symbol, None)
    if not chain: return

    # Get the furthest expiry date of the contracts
    expiry = sorted(chain, key = lambda x: x.expiry, reverse=True)[0].expiry
    
    # Select the call Option contracts with the furthest expiry
    calls = [i for i in chain if i.expiry == expiry and i.right == OptionRight.CALL]
    if len(calls) == 0: return

    # Select the ITM and OTM contract strike prices from the remaining contracts
    ordered_calls = sorted(calls, key=lambda x: x.strike)
    itm_call = ordered_calls[0]
    otm_call = ordered_calls[-1]</pre>
    </div>

    <li>In the <code class="csharp">OnData</code><code class="python">on_data</code> method, place the order.</li>

    <p>To place the order, call the <code class="csharp">OptionStrategies.BearCallSpread</code><code class="python">OptionStrategies.bear_call_spread</code> method with the details of each leg and then pass the result to the <code class="csharp">Buy</code><code class="python">buy</code> method.</p>

    <div class="section-example-container">
        <pre class="csharp">var optionStrategy = OptionStrategies.BearCallSpread(_symbol, itmCall.Strike, otmCall.Strike, expiry);
Buy(optionStrategy, 1);<br></pre>
        <pre class="python">option_strategy = OptionStrategies.bear_call_spread(self._symbol, itm_call.strike, otm_call.strike, expiry)
self.buy(option_strategy, 1)</pre>
    </div>

    <p>Alternatively, create a list of <code>Leg</code> objects and then call the <a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-market-orders"><span class='csharp'>Combo Market Order</span><span class='python'>combo_market_order</span></a>, <a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-limit-orders"><span class='csharp'>Combo Limit Order</span><span class='python'>combo_limit_order</span></a>, or <a href="/docs/v2/writing-algorithms/trading-and-orders/order-types/combo-leg-limit-orders"><span class='csharp'>Combo Leg Limit Order</span><span class='python'>combo_leg_limit_order</span></a> method.</p>
    <div class="section-example-container">
        <pre class="csharp">var legs = new List&lt;Leg&gt;()
    {
        Leg.Create(itmCall.Symbol, -1),
        Leg.Create(otmCall.Symbol, 1)
    };
ComboMarketOrder(legs, 1);</pre>
        <pre class="python">legs = [
    Leg.create(itm_call.symbol, -1),
    Leg.create(otm_call.symbol, 1)
]
self.combo_market_order(legs, 1)</pre>
    </div>
</ol>

