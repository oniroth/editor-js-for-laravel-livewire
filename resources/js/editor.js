import EditorJS from '@editorjs/editorjs';
import Header from '@editorjs/header';
import Image from '@editorjs/simple-image';
import List from '@editorjs/list';
import Checklist from '@editorjs/checklist';
import Quote from '@editorjs/quote';
import Code from '@editorjs/code';
import Table from '@editorjs/table';
import Delimiter from '@editorjs/delimiter';
import Embed from '@editorjs/embed';
import Marker from '@editorjs/marker';
import InlineCode from '@editorjs/inline-code';
import CodeTool from '@rxpm/editor-js-code';
import Title from "title-editorjs";
import Columns from '@calumk/editorjs-columns';
import DrawingTool from '@blade47/editorjs-drawing-tool';
import AttachesTool from '@editorjs/attaches';
import LinkTool from '@editorjs/link';
import ImageTool from '@editorjs/image';
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const holderElement = document.getElementById('editorjs');
const editor = new EditorJS({
    holder: 'editorjs',
    autofocus: true,
    tools: {
        attaches: {
            class: AttachesTool,
            config: {
                additionalRequestHeaders: {
                    "X-CSRF-TOKEN": token
                },
                endpoint: window.siteUrl + "/editor/upload",
            },
        },
        drawingTool:DrawingTool ,
        linkTool: {
            class: LinkTool,
            config: {
                endpoint: window.siteUrl + "/fetchUrl/",
            }
        },
        columns :{
            class: Columns,
            config:{
                EditorJsLibrary: EditorJS,
                tools:{
                    image: Image,
                    code: {
                        class: CodeTool,
                        config: {
                            modes: {
                                'js': 'JavaScript',
                                'py': 'Python',
                                'go': 'Go',
                                'cpp': 'C++',
                                'cs': 'C#',
                                'md': 'Markdown',
                                'php': 'php'
                            },
                            defaultMode: 'php',
                        }
                    },
                 delimiter : Delimiter,

             },
            },
        },
        title: Title,
        image: Image,
        imageTool: {
            class: ImageTool,
            config: {
                field: 'file',
                additionalRequestHeaders: {
                    "X-CSRF-TOKEN": token
                },
                endpoints: {
                    byFile: window.siteUrl + '/editor/upload',
                    byUrl:window.siteUrl + '/fetchUrl/',
                }
            }
        },
        list: List,
        // checklist: Checklist,
        quote: Quote,
        code: {
            class: CodeTool,
            config: {
                modes: {
                    'js': 'JavaScript',
                    'py': 'Python',
                    'go': 'Go',
                    'cpp': 'C++',
                    'cs': 'C#',
                    'md': 'Markdown',
                    'php': 'php'
                },
                defaultMode: 'php',
            }
        },
        table: Table,
        delimiter: Delimiter,
        embed: {
            class: Embed,
            inlineToolbar: true
        },
        marker: Marker,
        inlineCode: InlineCode,
    },
    placeholder: 'شروع به نوشتن کنید...',
    data: window.editorData || { blocks: [] },
    i18n: {
        messages: {
            /**
             * Other below: translation of different UI components of the editor.js core
             */
            ui: {

                "blockTunes": {
                    "toggler": {
                        "Click to tune": "برای سفارشی سازی کلیک کنید",
                        "or drag to move": "یا با کشیدن جابجا کنید"
                    },
                },
                "inlineToolbar": {
                    "converter": {
                        "Convert to": "تبدیل به"
                    }
                },
                "toolbar": {
                    "toolbox": {
                        "Add": "افزودن",

                    }
                },
                "popover": {
                    "Convert to": "تبدیل به",
                    "Filter": "فیلتر",
                    "Nothing found": "چیزی پیدا نشد",

                },
            },
            toolNames: {
                "Text": "متن",
                "List": "لیست",
                "Warning": "اخطار",
                "Checklist": "چک لیست",
                "Quote": "نقل قول",
                "Code": "کد",
                "Delimiter": "خط جدا کننده",
                "Table": "جدول",
                "Link": "لینک",
                "Marker": "مارکر",
                "Bold": "بولد",
                "Italic": "ایتالیک",
                "Attachment" : "افزودن فایل",
                "Drawing" : "تخته سفید",
                "Columns" : "ردیف",
                "Title" : "عنوان",
                "Image" : "تصویر",
                "Unordered List" : "لیست",
                "Ordered List" : "لیست عددی",
            },


            tools: {
              "attaches" : {
                    "File title" : "عنوان فایل",
              },
                "linkTool": {
                    "Link": "افزودن لینک"
                },
                "stub": {
                    'The block can not be displayed correctly.': 'این بلاک به درستی نمایش داده نمیشود'
                }
            },
            blockTunes: {

                "delete": {
                    "Delete": "حذف",
                    "Click to delete": "برای حذف کلیک کن"
                },
                "moveUp": {
                    "Move up": "یک بلوک به بالا"
                },
                "moveDown": {
                    "Move down": "یک بلوک به پایین"
                }
            }

        },

    },
    onReady: () => {
        console.log('Editor is ready');
    }

});

// For save content in Livewire
window.addEventListener('save-all-content', async () => {
    const outputData = await editor.save();
    const outputDataString = JSON.stringify(outputData);

    Livewire.dispatch('storePost', { outputData: outputDataString });
});



