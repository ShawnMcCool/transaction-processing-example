# Persistence Without ORM

I'm probably going to take a lot of shit for this. But in a conversation with friends, I slammed out this example of object persistence without ORMs.

It took me ONE HOUR to make this. It's not tested, and I'm sure it won't run. It probably has syntax errors. Probably the database interactions are profoundly imperfect. However, my point stands. 

Everything in its place and a place for everything. Mappings are explicit and manually built. They are tested thoroughly through normal use if nothing else. Through use case testing is the most normal way. But you can unit test it all if you like (I usually do).

With code like this, never will I have to deal with a generalized monolithic ORM and the way that it forces my application to compromise on design quality. No Active Record entities.. No inundations of annotations.

It codes up fast and easy and once it's written it's written and you don't have to revisit it until you have a reason to do so. When you do, the change is generally simple or at least proportional to the intended different in outcome.

Mostly the SQL queries end up simple because we're just storing objects in the database people. We don't have a bunch of crazy ORM relationships to implement. It's an object that contains other objects. We store the Transaction into a repository. NOTICE THAT THERE IS NOT A CAPTURE REPOSITORY. Despite the queries USUALLY being incredibly simple.. They don't have to be. They can be whatever you want.

> Does some of this feel like boiler-plate? Guess what. Those are the MOST important parts of the application. Those are the places where the mappings bring great flexibility and opportunity. Those are not to be traded away because we don't want to spend 20 minutes writing the code. If you want to use a query-builder, fine. So long as your tool doesn't impact the design of your actual objects.
>
> I personally find SQL to be an amazing high-level interface and I don't know why we try to avoid it so much...