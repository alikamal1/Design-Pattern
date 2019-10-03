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


# **Design Pattern**
Design Pattern are typical solution to common problems in software design.  Each pattern is like a blueprint that you can customize to solve a particular design problem in your code.

**Creational Patterns** provide object creation mechanisms that increase flexibility and reuse of existing code

**Structural Patterns** explain how to assemble objects and classes into larger structures, while keeping the structures flexible and efficient

**Behavioral Patterns** take care of effective communication and the assignment of reponsibilities between objects

### Reference
https://refactoring.guru