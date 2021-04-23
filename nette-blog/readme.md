Úkoly na DB
=================

- Výpsat jedním dotazem 3 sloupce. V prvním bude id client.id. V druhém bude název client_person.name a v třetím bude
  client.name
  SELECT client.id, client_person.name, client.name FROM client INNER JOIN client_person ON client.id = client_person.client_id

- Stejný výpis jako úkol nad ale vypsat jen firmy, které obsahují v názvu řetězec `any` To znamená že to musí najít
  firmu `anything` ale i firmu `many`. Bez fulltext.
  SELECT client.id, client_person.name, client.name FROM client INNER JOIN client_person ON client.id = client_person.client_id WHERE client.name LIKE '%any%'

- Vypsat jedním dotazem pouze ty clienty kteří mají aspoň dva client_person.
  SELECT client.id, client.name, (SELECT COUNT(*) FROM client_person WHERE client.id = client_person.client_id) as contact_count FROM client HAVING contact_count > 1


