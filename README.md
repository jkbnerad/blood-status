## Získávání informací o stavu zásob krve u vybraných transfuzních stanic.

#### Popdporované stanice

- Klatovy
- Liberec
- Všeobecná faktulní nemocnice v Praze
- Ústav hematologie a krevní transfuze v Praze
- Trutnov

Data jsou zatím ukládána do Google Sheetu

https://docs.google.com/spreadsheets/d/1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw/edit?usp=sharing

#### Spuštění

```
php app.php app:klatovy --secretJson privacy/google.json --sheetId 1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw
php app.php app:liberec --secretJson privacy/google.json --sheetId 1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw
php app.php app:vfn --secretJson privacy/google.json --sheetId 1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw
php app.php app:uhkt --secretJson privacy/google.json --sheetId 1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw
php app.php app:trutnov --secretJson privacy/google.json --sheetId 1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw
```
