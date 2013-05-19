SBK.PaginatedHTML = SBK.Class.extend({
    init: function (subject, page_header_selector, page_class_name) {
        var self = this;

        self.container = subject.parent();
        self.page_class_name = page_class_name;
        self.to_be_processed = jQuery('<div id="to_be_processed"></div>').appendTo(self.container);
        self.to_be_processed.html(subject.html());
        subject.remove();
        //A4 empirically measured
        self.page_height = 1000;
        self.page_width = 800;
        self.no_of_columns = 2;
        self.in_process = jQuery('<div id="in_process"></div>').appendTo(self.container);
        self.in_process.width(self.page_width);

        header = jQuery(page_header_selector, self.to_be_processed);
        header.remove();
        content = jQuery(self.to_be_processed.html());
        self.to_be_processed.remove();
        self.break_lines(header, content);
    },

    break_lines: function (header, content) {
        var self = this, index, lines, header, page = [], page_count = 0, column_count = 0;

        self.initialise_page_holder(header, page_count);
        for (index = 0; index < content.length; index = index + 1) {
            self.td[column_count].append(content[index]);
            if (self.in_process.height() > self.page_height) {
               jQuery('>:last', self.td[column_count]).remove();
               column_count = column_count + 1;
               if(column_count > self.no_of_columns - 1) {
                   page[page_count] = self.add_page(self.in_process.html(), page_count);
                   self.initialise_page_holder(header, page_count); 
                   page_count = page_count + 1;
                   column_count = 0;
                }
               self.td[column_count].append(content[index]);
            }
        }
        for(index_2 = column_count + 1; index_2 < self.no_of_columns; index_2 = index_2 + 1) {
            self.td[index_2].remove();
        }
        page[page_count] = self.add_page(self.in_process.html(), page_count);

        for (index = 0; index < page.length; index = index + 1) {
            jQuery('.page_header .pagenumber #page_number', page[index]).html(index + 1);
            jQuery('.page_header .pagenumber #number_of_pages', page[index]).html(page.length);
        }
        self.in_process.remove();
    },
    
    initialise_page_holder: function (header, page_count) {
        var self = this, table, tbody, tr, index;
        
        self.in_process.html('');
        self.in_process.append(header);
        self.in_process.removeClass();
        self.in_process.addClass('paginated-page');
        self.in_process.addClass('page_' + page_count);
        self.in_process.addClass(self.page_class_name);
        table = jQuery('<table></table>').appendTo(self.in_process);
        tbody = jQuery('<tbody></tbody>').appendTo(table);
        tr = jQuery('<tr></tr>').appendTo(tbody);
        self.td = [];
        for (index = 0; index < self.no_of_columns; index = index + 1) {
            self.td[index] = jQuery('<td class="column_' + index + '"style="width: ' + (100 / self.no_of_columns) + '%;"></td>').appendTo(tr);
        }
    },
    
    add_page: function (page_content, page_count) {
        var self = this, page;

        page = jQuery('<div></div>').appendTo(self.container);
        page.addClass('paginated-page');
        page.addClass('page_' + page_count);
        page.addClass(self.page_class_name);
        
        page.width(self.page_width);
        page.height(self.page_height);
        page.html(page_content);
        
        return page;
    }
});