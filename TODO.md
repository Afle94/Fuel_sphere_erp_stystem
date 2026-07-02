# TODO

- [x] 1) Update `resources/views/purchase.blade.php`: add a dedicated “Save Entry” button for the pending items list.
- [x] 2) Ensure the button submits the same `purchaseForm` so JS hidden inputs `items[index][field]` are posted to `PurchaseController@storepurchase`.
- [x] 3) Keep existing Remove button logic untouched.
- [ ] 4) Test flow manually:


  - [ ] Add Items -> Accept -> pending row appears
  - [ ] Remove works
  - [ ] Click Save Entry -> data saved
  - [ ] Refresh page -> pending rows do not reappear

