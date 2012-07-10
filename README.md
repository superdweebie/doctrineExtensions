DoctrineExtensions
=====================

[![Build Status](https://secure.travis-ci.org/superdweebie/doctrineExtensions.png)](http://travis-ci.org/superdweebie/doctrineExtensions)

A collection of php 5.4 traits, Documents, Annotations, Subscribers and Filters
that can be used to extend doctrine.

At present only MongoDB ODM is supported.

Each extension is packaged separately. Multiple extensions can be loaded with the Manifest, which will automatically
resolve dependencies between extensions.

Extensions included are:

##Access Control

Permissions based access to documents. Supports permissions for Create, Read, Update and Delete.

Permissions must have two attributes:
    Role
    Action

Permissions optionally support a state attribute also.

Access Control can be extended to support other actions beyond Create, Read, Update and Delete. Several other
extensions have extended access control support.

##Audit

Will generate embedded audit documents for fields annotated with @audit. Audit documents record changes to the field, who made the
changes, and when the changes were made.

##Auth

Provides a trait for salted string encryption.

##DoNotHardDelete

Documents annotated with @DoNotHardDelete cannot be deleted from the database.

##Freeze

Documents with the Freeze ability can be frozen and thawed. When frozen, they cannot be updated or deleted.

Supports username and timestamping on freeze and thaw events.

Supports access control on freeze and thaw actions.

##Readonly

Fields annotated with @readonly cannot be updated

##Serializer

Will serialize all the fields of a document into an array or json. Fields may be annotated @doNotSerialize, to be skipped
during serialization.

##SoftDelete

Documents with the SoftDelete ability can be softDeleted and restored. When softDeleted, they cannot be updated, but
can be fully deleted from the database.

Supports username and timestamping on softDelete and restore events.

Supports access control on softDelete and restore actions.

##Stamp

Timestamp and Userstamp documents on create and update.

##State

Gives documents a state. Used by Access Control and Workflow extensions.

##UiHints

Allows fields to be annotated with extra metadata which may be helpful to ui rendering

##User

Traits for user documents.

##Workflow

Allows the enforcement of document state changes to a predefined list of transitions. Ie: documents
may be forced to follow the state path of: draft->approved->published

##Zone

Allows a document to belong to several zones, and app wide filtering of those zones.

Install
=======

Add the following to your composer.json:

    "require": {
        "superdweebie/Sds\DoctrineExtensions": "dev-master"
    }

Usage
=====

The easiest way to use Sds\DoctrineExtensions is to populate a `manifestConfig` with the
extensions you want to use, and pass it to a `manifest`. For example, this configures
SoftDelete and Zone:

        $manifestConfig = new ManifestConfig(
            $myAnnotationReader,
            array(
                'Sds\DoctrineExtensions\SoftDelete' => null,
                'Sds\DoctrineExtensions\Zone' => null
            $this->activeUser
        );

        $manifest = new Manifest($manifestConfig);

You can then use the manifest when you are configuring Doctrine with the following four methods:

        $manifest->getDocuments();
        $manifest->getFilters();
        $manifest->getSubscribers();
        $manifest->getAnnotations();

Exceptions
==========

Most of the extensions do significant work in Doctrine's onFlush event. When an action is attempted
which is not allowed, the extensions will not raise an exception. Rather, they will raise an event.
This means the whole flush process isn't aborted. If you need to raise an exception, then add an
event listener to catch the event, and raise the exception yourself.

Further Documentation
=====================

A serious attempt has been made to make the code self documenting. Look inside each extension, and you will
find information about filters in the filters directory, information about events in the events directory, etc.

All configuration options for each extension can be found in the extension's ExtensionConfig.php

Writing Your Own Extensions
===========================

You can simply wirte your own extensions that can be loaded with the manifest. The only requirement is are `Extension` and
`ExtensionConfig` classes.