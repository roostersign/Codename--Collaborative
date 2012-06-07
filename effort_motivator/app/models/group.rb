class Group < ActiveRecord::Base
  belongs_to :teacher

  has_many :games
end
