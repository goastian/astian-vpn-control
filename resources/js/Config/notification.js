import { Notyf } from 'notyf';
import 'notyf/notyf.min.css';

export const notyf = new Notyf({
    duration: 5000,
    position: {
        x: 'right',
        y: 'top',
    },
    types: [
        {
            type: 'warning',
            background: '#FFEE58',
            dismissible: false,
            icon: {
                className: 'material-icons',
                tagName: 'i',
                text: 'warning'
            },
        }
    ]
});