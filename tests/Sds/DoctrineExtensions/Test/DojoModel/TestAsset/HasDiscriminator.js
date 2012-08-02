// This code generated by Sds\DoctrineExtensions\DojoModel

define ([
        'dojo/_base/declare',
        'dojo/Stateful'
    ],
    function (
        declare,
        Stateful
    ){
        // module:
        //		Sds/DoctrineExtensions/Test/DojoModel/TestAsset/Document/HasDiscriminator

        return declare (
            'Sds.DoctrineExtensions.Test.DojoModel.TestAsset.Document.HasDiscriminator',
            [Stateful],
            {
                // summary:
                //		A mirror of doctrine document Sds\DoctrineExtensions\Test\DojoModel\TestAsset\Document\HasDiscriminator
                //
                // description:
                //      Use this to create documents in a browser which can the be passed to a doctrine server for
                //      persistence

                // _type: string
                //      The doctrine discriminator property. Don't change this one!
                _type: 'hasDiscriminator',

                // id: custom_id
                id: undefined,

                // name: string
                name: undefined,

                toJSON: function(){
                    // summary:
                    //     Function to handle serialization

                    var json = {};
                    if (this.get('_type')) {
                        json['_type'] = this.get('_type');
                    }
                    if (this.get('id')) {
                        json['id'] = this.get('id');
                    }
                    if (this.get('name')) {
                        json['name'] = this.get('name');
                    }

                    return json;
                }
            }
        );
    }
);
