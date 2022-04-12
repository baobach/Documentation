
<p>
Follow these steps to manually create a consolidator:</p><p>1) Create a consolidator object (TradeBarConsolidator, QuoteBarConsolidator, TickConsolidator, or TickQuoteConsolidator) and pass the constructor a max count and a time period.</p><p>2) Define the consolidation event handler</p><p>3) Add the event handler to the consolidator</p>

<div class="section-example-container">
<pre class="python">def Initialize(self):
    self.consolidator = TradeBarConsolidator(25, timedelta(minutes=30))
    self.consolidator.DataConsolidated += self.consolidation_handler
    
def consolidation_handler(self, sender, consolidated_bar):
    # Bar period is now 30 min from the consolidator above.
    self.Debug(str(consolidated_bar.EndTime - consolidated_bar.Time) + " " + consolidated_bar.ToString())</pre>
<pre class="csharp">public override void Initialize()
{ 
    _consolidator = new TradeBarConsolidator(25, TimeSpan.FromMinutes(30));
    _consolidator.DataConsolidated += ConsolidationHandler;
}

private void ConsolidationHandler(object sender, TradeBar consolidatedBar) {
    // Bar period is 30 min from the consolidator above.
    Debug((consolidatedBar.EndTime - consolidatedBar.Time).ToString() + " " + consolidatedBar.ToString());
}</pre>
</div>