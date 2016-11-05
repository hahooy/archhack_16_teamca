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
    var username: String?
    var description: String?
    var pub_time_interval: NSTimeInterval?
    var thumbnail_base64: String?
    
    init(id: Int?, username: String?, description: String?,pub_time_interval: NSTimeInterval?,
         thumbnail_base64: String?) {
        self.id = id
        self.username = username
        self.description = description
        self.pub_time_interval = pub_time_interval
        self.thumbnail_base64 = thumbnail_base64
    }
    
    convenience init() {
        self.init(id: nil,username: nil,description: nil,pub_time_interval: nil,thumbnail_base64: nil)
    }
}
