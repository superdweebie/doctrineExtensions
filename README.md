SdsDoctrineExtensions
=====================

A collection of php 5.4 traits, Doctrine Documents, Annotations and Listeners 
that can be used to extend doctrine.

At present only MongoDB ODM is supported.


Access Control
==============

Permissions based access to documents

ActiveUser
==========

Will inject the active user / identity into documents

Audit
=====

Will generate embedded audit documents for annotated fields

Metadata
========

Extensions for annotations to provide hints to UI layer

Readonly
========

Creates readonly fields

Serializer
==========

Controls the serialization of documents into JSON

SoftDelete
==========

Make documents soft deletable

Stamp
=====

Timestamp and Userstamp documents on create and modify

User
====

Traits for basic user functionality