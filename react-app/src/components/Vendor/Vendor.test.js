import React from 'react';
import ReactDOM from 'react-dom';
import Vendor from './Vendor';


it('Vendor renders without crashing', () => {
    const div = document.createElement('div');
    ReactDOM.render(<Vendor />, div);
});