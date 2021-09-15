Run deploy.sh to deploy the project

Run composer test, test-unit or test functional in the www folder to run the tests

Decisions:
- Tried to at least implement the port/adapter pattern to have separated infrastructure/application layers
- Ideally I wished to use DoctrineORM to make a more robust Entity and Repositories structure, but since I don't have
  much experience with Doctrine I choose to use only DBAL. Sadly this left out utilities like Doctrine Entity Manager 
  out.
- For simplicity, I used simple integer IDs in the database, mainly because since we don't have Create/Update/Delete 
  concerns. In a scenario with this concern, I would have used UUIDs, or if I have experience, delegate the process
  to gave identity to entities to DoctrineORM.
- Category it's a ValueObject by itself, and it's close to be a Entity with itself. I promoted Discounts and Prices to 
  ValueObjects too, since it seems like them would be immutable and time limited data. Ideally, currency itself can be 
  a ValueObject, but I didn't want to add more complexity.
- The Products Entity itself its maybe too much anemic. In a more evolved scenario, it should do things like validate its
  own data.
- DTOs are also fairly simple, but in a more evolved system they could also take validation responsibilities.
- I used MySQL as DB, it probably is not the fastest for Discounted Products search. I think of this code as part 
  of a CQRS pattern, where the data of the master Database it's modeled in a Json format to be pushed in more capable
  database system for search, like an ElasticSearch.
  
Notes:
- I have detected and edge case in the deploy.sh, that it can happen that the DB migration its started before the
  database container its fully operational. I added a sleep of 2 minutes to give it some time, but its seems like
  it's only a problem in my local environment. Sorry for the inconvenience