/**
 * @category   Creativestyle
 * @package    Creativestyle_AffiliNet
 * @copyright  Copyright (c) 2014 creativestyle GmbH
 * @author     Marek Zabrowarny / creativestyle GmbH <support@creativestyle.de>
 */
if (!window.AffiliNet) var AffiliNet = {};

AffiliNet.StatisticsGrid = Class.create();

AffiliNet.StatisticsGrid.prototype = {
    initialize: function(container, groups, subs, buttonContainerSelector) {
        this.container = $(container);
        this.table = $(container + '_table');
        buttonContainerSelector = buttonContainerSelector || '.filter-actions';
        if (this.container.select(buttonContainerSelector).length) {
            this.buttonContainer = this.container.select(buttonContainerSelector).first();
        }
        this.groups = groups || {};
        this.subs = subs || {};
        this.setupHeaders().setupButtons();
    },

    setupHeaders: function() {
        var headers = this.table.select('thead th');
        this.table.select('thead').first()
            .insert({top: this.getGroupHeaders(headers), bottom: this.getSubHeaders(headers)});
        return this;
    },

    setupButtons: function() {
        ['overview', 'sales', 'leads', 'commission'].each(function(group) {
            button = this.addButton(group, this.groups[group]);
            if (group != 'overview') AffiliNet.StatisticsGrid.toggleGroup(group, button);
        }.bind(this));
        return this;
    },

    getGroupHeaders: function(headers) {
        var groupHeaders = [], groupHeadersRow = new Element('tr', {class: 'group_headings'});
        headers.each(function(header) {
            var headerGroupClass = $w(header.className).detect(function(className) { return className.include('_group') });
            var groupKey = headerGroupClass ? headerGroupClass.gsub(/_group/, '') : null;
            if (groupHeaders.length && groupKey == groupHeaders.last().key) {
                groupHeaders.last().count++;
            } else {
                var groupTitle = groupKey
                    ? typeof this.groups[groupKey] != 'undefined'
                        ? this.groups[groupKey]
                        : groupKey.capitalize()
                    : null;
                groupHeaders.push({key: groupKey, title: groupTitle, count: 1});
            }
        }.bind(this));

        // generate row of grouping headers
        groupHeaders.each(function(group) {
            var groupHeader = new Element('th', {class: group.key ? group.key + '_group' : null, colspan: group.count}).update(group.title);
            groupHeadersRow.insert({bottom: groupHeader})
        });
        groupHeadersRow.select('th').last().addClassName('last');

        return groupHeadersRow;
    },

    getSubHeaders: function(headers) {
        var subHeaders = [], subHeadersRow = new Element('tr', {class: 'headings'});
        headers.each(function(header) {
            var subheaderClass = $w(header.className).detect(function(className) { return className.include('_subheader') });
            var subheaderValueClass = $w(header.className).detect(function(className) { return className.include('_subvalue') });
            var subKey = subheaderClass ? subheaderClass.gsub(/_subheader/, '') : null;
            var subValue = subheaderValueClass ? subheaderValueClass.gsub(/_subvalue/, '') : null;
            if (subHeaders.length && subKey == subHeaders.last().key) {
                subHeaders.last().count++;
            } else {
                subHeaders.push({key: subKey, count: 1, values: []});
            }
            if (subValue) {
                var subTitle = typeof this.subs[subValue] != 'undefined'
                    ? this.subs[subValue]
                    : subValue.capitalize();
                subHeaders.last().values.push(subTitle);
            }
        }.bind(this));

        // generate subheaders row
        var counter = 0;
        subHeaders.each(function(sub) {
            if (sub.values.length) {
                sub.values.each(function(subValue) {
                    var subHeader = new Element('th').update('<span class="nobr">' + subValue + '</span>');
                    var subHeaderClasses = $w(headers[counter].className).findAll(function(className) {
                        return !(className.include('_subvalue') || className.include('_subheader'));
                    });
                    subHeaderClasses.each(function(className) {
                        subHeader.addClassName(className);
                    });
                    subHeadersRow.insert({bottom: subHeader});
                });
                headers[counter].colSpan = sub.count;
                headers[counter].addClassName('has_sub_headings');
                headers[counter].update('<span class="nobr">' + headers[counter].innerHTML.stripTags().sub(/(.*?)(\s+\(.*?\))/, '#{1}') + '</span>');
                for (var i = 1; i < sub.count; i++) {
                    if (headers[counter + i].hasClassName('last')) headers[counter].addClassName('last');
                    headers[counter + i].remove();
                }
            } else {
                for (var i = 0; i < sub.count; i++) {
                    headers[counter + i].rowSpan = 2;
                }
            }
            counter += sub.count;
        });
        subHeadersRow.select('th').last().addClassName('last');

        return subHeadersRow;
    },

    addButton: function(key, title) {
        if (this.buttonContainer) {
            var button = new Element('button', {type: 'button', class: 'scalable save'}).update('<span><span><span>' + title + '</span></span></span>');
            Event.observe(button, 'click', AffiliNet.StatisticsGrid.toggleGroup.bind(this, key, button));
            this.buttonContainer.insert(button);
            return button;
        }
        return null;
    }

};

AffiliNet.StatisticsGrid.toggleGroup = function(key, button) {
    $$('.' + key + '_group').invoke('toggle');
    button.toggleClassName('save');
}
