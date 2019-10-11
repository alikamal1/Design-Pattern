# **Object-Oriented Programming**
 is a paradigm based on the concept of warpping pieces of data and behavior related to that data into special bundles called **objects** which constructed from set of blueprints called **classes**

Data stored inside the object's field is often referenced as **state** and all the object's method define its **behavior**

# Pillar of OOP
**Abstraction** is a model of real-world object or phenomenon limited to a specific context, which represents all details relevant to this context with high accuracy and omits all the rest

**Encapsulation** is the ability of an object to hide parts of its state and behaviors from other objects exposing only a limited interface to the rest of the program

**Inheritance** is the ability to build new classes on top of existing ones for code reuse by extending class and put extra functionality into resulting subclass which inherits fields and methods of the superclass

**Polymorphism** is the ability of a program to detect the real class of an object and call its implementation even when its real type is unknown in the current context

# Relations between Objects
**Dependency** class A can be affected by changes in class B

**Association** object A knows about object B, Class A depends on B

**Aggregation** object A knows about object B, and consists of B. Class A depends on B

**Composition** object A knows about object B, consists of B and manages B life cycle. class A depends on B

**Implementation** class A defines methods declared in interface B. objects A can be treated as B. class A depends on B

**Inheritance** class A inherits interface and implementation of class B but can extend it. object A can be treated as B. class A depends on B

# Design Principles
**Encapsulate what varies** identify the aspects of your application that vary and separate them from what stays the same

**Program to an Interface not to an Implementation** depend in abstractions not on concrete classes

**Favor Composition over Inheritance** subclass can't reduce the interface of the superclass. when overriding methods you need to make sure that the new behavior is compatible with the base one. Inheritance breaks encapulation of the superclass. subclasses are tightly coupled to superclasses. tring to reuse code throught inheritance can lead to creating parallel inheritance hierarchies

# Soild Principles
intended to make software designs more understandable, flexible and maintainable 
**Single Responsibility Principle** class should have just one reson to change

**Open/Closed Principle** classes should be open for extension but closed for modification

**Liskov Substitution Principle** when extending a class remember that you should able to pass objects of the subclass in place of objects of the parent class without breaking the client code

**Interface Segregation Principle** clients shouldn't be forced to depend on methods the do not use

**Dependency Inversion Principle** hight-level classes shouldn't depend on low-level classes both sould depend on abstractions. Abstractions shouldn't depend on details. Details should depend on abstractions

# **Design Pattern**
Design Pattern are typical solution to common problems in software design.  Each pattern is like a blueprint that you can customize to solve a particular design problem in your code.

## Creational Patterns
provide object creation mechanisms that increase flexibility and reuse of existing code

[**Factory Method**](https://github.com/alikamal1/Design-Pattern/blob/master/Creational/Factory_Method.php){:target="_blank"} provides an interface for creating objects in a superclass but allows subclasses to alter the type of objects that will be created

**Abstract Factory** lets you produce families of related objects without specifying their concrete classes

[**Builder**](https://github.com/alikamal1/Design-Pattern/blob/master/Creational/Builder.php){:target="_blank"} lets you construct complex objects step by step. the pattern allows you produce different types and representations of an object using the same construction code

[**Prototype**](https://github.com/alikamal1/Design-Pattern/blob/master/Creational/Prototype.php){:target="_blank"} lets you copy existing objects without making your code dependent on their classes

[**Signleton**](https://github.com/alikamal1/Design-Pattern/blob/master/Creational/Singleton.php){:target="_blank"} lets you ensure that a class has only one instance, while providing a gloabl access point to this instance

## Structural Patterns
explain how to assemble objects and classes into larger structures, while keeping the structures flexible and efficient

[**Adapter**](https://github.com/alikamal1/Design-Pattern/blob/master/Structural/Adapter.php){:target="_blank"} allows objects with incompatible interfaces to collaborate

[**Bridge**](https://github.com/alikamal1/Design-Pattern/blob/master/Structural/Bridge.php){:target="_blank"} lets you split a large class or a set of closely related classes into two separate hierarchies-abstraction and implementaion which can be developed independently of each other

**Composite** lets you compose objects into tree structure and then work with these structures as if they were indicidual objects

**Decorator** let you attach new behaviors to objects by placing these obejcts inside a special wrapper objects that contain the behaviors

[**Facade**](https://github.com/alikamal1/Design-Pattern/blob/master/Structural/Facade.php){:target="_blank"} provide a simplified interface to a library, a framework or any other complex set of classes

**Flyweight** let you fit more objects into the avaible amount of RAM by sharing common parts of state between multiple objects instead of keeping all of the data in each object

[**Proxy**](https://github.com/alikamal1/Design-Pattern/blob/master/Structural/Proxy.php){:target="_blank"} let you provide a substitute or placehoder for another object. A proxy control access to original object allowing you to preform something either before or after the request gets throught to the original object

## Behavioral Patterns
take care of effective communication and the assignment of reponsibilities between objects

**Chain of Responsibility** lets you pass requests along a chain of handlers. upon receiving a request each handler decides either to process the request or to pass it to the next handler in the chain

[**Command**](https://github.com/alikamal1/Design-Pattern/blob/master/Behavioral/Command.php){:target="_blank"} turns a request inot a stand-alone object that contains all information about the request. this transformaion lets you parameterize methods with different request, delay or queue a request's excution and support undoable operations 

**Iterator** lets you traverse elements of a collection without exposing its underlying representataion (list, stack, tree, etc.)

**Mediator** lets you reduce chaotic dependencies between objects. the parttern restricts direct communications between the objects and forces them to collaborate only via a mediator object

**Memento** lets you save and restore the previous state of an object without revealing the details of its implementation

[**Observer**](https://github.com/alikamal1/Design-Pattern/blob/master/Behavioral/Observer.php){:target="_blank"} lets you define a subscription mechanism to notify multiple objects about any events that happen to the object they're observing

**State** lets an object alter its behavior when its internal state changes. it appears as the object changed ints class

[**Strategy**](https://github.com/alikamal1/Design-Pattern/blob/master/Behavioral/Strategy.php){:target="_blank"} lets you define a family of algorithms put each of them into a separate class and make their objects interchangeable

**Template method** defines the skeleton of an algorithm in the superclass but lets subclasses override specific steps of the algorithm without changing its structure

**Visitor** lets you separate algorithms from the objects on which they operate

### Reference
https://refactoring.guru