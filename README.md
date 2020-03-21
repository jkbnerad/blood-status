## Získávání informací o stavu zásob krve u vybraných transfuzních stanic.

#### Popdporované stanice

- Klatovy
- Liberec

Data jsou zatím ukládána do Google Sheetu

https://docs.google.com/spreadsheets/d/1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw/edit?usp=sharing

#### Spuštení

```
php app.php app:klatovy --secretJson privacy/google.json --sheetId 1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw
php app.php app:liberec --secretJson privacy/google.json --sheetId 1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw
```
