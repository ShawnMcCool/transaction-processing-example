# Persistence Without ORM

I'm probably going to take a lot of shit for this. But in a conversation with friends, I slammed out this example of object persistence without ORMs.

It took me ONE HOUR to make this. It's not tested, and I'm sure it won't run. It probably has syntax errors. Probably the database interactions are profoundly imperfect. However, my point stands. 

Everything in its place and a place for everything. Mappings are explicit and manually built. They are tested thoroughly through normal use if nothing else. Through use case testing is the most normal way. But you can unit test it all if you like (I usually do).

With code like this, never will I have to deal with a generalized monolithic ORM and the way that it forces my application to compromise on design quality. No Active Record entities.. No inundations of annotations.

It codes up fast and easy and once it's written it's written and you don't have to revisit it until you have a reason to do so. When you do, the change is generally simple or at least proportional to the intended different in outcome.

Mostly the SQL queries end up simple because we're just storing objects in the database people. We don't have a bunch of crazy ORM relationships to implement. It's an object that contains other objects. We store the Transaction into a repository. NOTICE THAT THERE IS NOT A CAPTURE REPOSITORY. Despite the queries USUALLY being incredibly simple.. They don't have to be. They can be whatever you want.

## Isn't this boiler-plate?

Does some of this feel like boiler-plate? Guess what. Those are the MOST important parts of the application. Those are the places where the mappings bring great flexibility and opportunity. Those are not to be traded away because we don't want to spend 20 minutes writing the code. If you want to use a query-builder, fine. So long as your tool doesn't impact the design of your actual objects.

I personally find SQL to be an amazing high-level interface and I don't know why we try to avoid it so much...

When we make compromises to avoid the "tedium" of mapping. Not only is the code generally impacted by our persistence tooling. But we lose mapping.

## Mappings are Boundaries

When we map from one thing to another, that becomes an articulation point that allows EACH side of the mapping to change independently.

Independent evolution is the property of our systems that we are MISSING. This is why our applications get locked into stasis and new features / feature modifications get increasingly more expensive. Ask yourself.. why shouldn't adding behavior to a system be FASTER because the system has more capabilities? Why is it always slower? It's because we've coupled everything to everything and now it must all evolve in lock-step. It's a fundamental failing of software design.

> These boundaries ARE the key to evolvable software, yet we outsource the mappings to monolithic general purpose tools that handle the complexity by completely ignoring it.

## But how do I handle all of my "relationships"?

Relationships are the result of a clearly flawed data-model-first paradigm of software design.

Design objects that serve a purpose, in this example it's Transaction. Transactions have multiple Captures. This would normally be communicated in active record by having a Transaction entity with a "has many" relationship to Capture entities. There's no reason for captures to be able to save themselves. They're a part of the Transaction model. That's why Transaction has a repository and Capture doesn't.

Because you can't save captures independently, you are prevented from violating the rules within Transaction.

Relationships were never a good idea.

## Data Access Between Contexts

Instead, create individual "contexts" like TransactionProcessing in your applications. Define the models needed for those. Then create OTHER contexts to serve OTHER concerns. Do you need state from the TransactionProcessing? Listen to the events and construct local state from that. Does this seem wasteful? Maybe the code that you're writing is TransactionProcessing code then? Or... maybe you actually have a lot to benefit from the fact that these entirely different contexts communicate only over a defined event / interface contract and are now able to evolve independently of one another..

> This solves the only REAL problem that we have.. the same real problem that we create over and over again.. systems that are so highly coupled that they become expensive or impossible to change.