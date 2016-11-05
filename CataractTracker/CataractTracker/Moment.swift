//
//  Moment.swift
//  CataractTracker
//
//  Created by Junyuan Suo on 11/4/16.
//  Copyright © 2016 JYLock. All rights reserved.
//

import Foundation

class Moment {
    var id: Int?
    var title: String?
    var username: String?
    var area: Int?
    var description: String?
    var createtime: String?
    var image_url: String?
    var thumbnail_url: String?
    
    init(id: Int?, title: String?, username: String?, area: Int?, description: String?, createtime: String?, image_url: String?, thumbnail_url: String?) {
        self.id = id
        self.title = title
        self.username = username
        self.area = area
        self.description = description
        self.createtime = createtime
        self.image_url = image_url
        self.thumbnail_url = thumbnail_url
    }
    
    convenience init() {
        self.init(id: nil, title: nil, username: nil, area: nil, description: nil, createtime: nil, image_url: nil, thumbnail_url: nil)
    }
}
